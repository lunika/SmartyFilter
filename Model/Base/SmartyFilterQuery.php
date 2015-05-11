<?php

namespace SmartyFilter\Model\Base;

use \Exception;
use \PDO;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;
use SmartyFilter\Model\SmartyFilter as ChildSmartyFilter;
use SmartyFilter\Model\SmartyFilterI18nQuery as ChildSmartyFilterI18nQuery;
use SmartyFilter\Model\SmartyFilterQuery as ChildSmartyFilterQuery;
use SmartyFilter\Model\Map\SmartyFilterTableMap;

/**
 * Base class that represents a query for the 'smarty_filter' table.
 *
 *
 *
 * @method     ChildSmartyFilterQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildSmartyFilterQuery orderByActive($order = Criteria::ASC) Order by the active column
 * @method     ChildSmartyFilterQuery orderByFiltertype($order = Criteria::ASC) Order by the filtertype column
 * @method     ChildSmartyFilterQuery orderByCode($order = Criteria::ASC) Order by the code column
 *
 * @method     ChildSmartyFilterQuery groupById() Group by the id column
 * @method     ChildSmartyFilterQuery groupByActive() Group by the active column
 * @method     ChildSmartyFilterQuery groupByFiltertype() Group by the filtertype column
 * @method     ChildSmartyFilterQuery groupByCode() Group by the code column
 *
 * @method     ChildSmartyFilterQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildSmartyFilterQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildSmartyFilterQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildSmartyFilterQuery leftJoinSmartyFilterI18n($relationAlias = null) Adds a LEFT JOIN clause to the query using the SmartyFilterI18n relation
 * @method     ChildSmartyFilterQuery rightJoinSmartyFilterI18n($relationAlias = null) Adds a RIGHT JOIN clause to the query using the SmartyFilterI18n relation
 * @method     ChildSmartyFilterQuery innerJoinSmartyFilterI18n($relationAlias = null) Adds a INNER JOIN clause to the query using the SmartyFilterI18n relation
 *
 * @method     ChildSmartyFilter findOne(ConnectionInterface $con = null) Return the first ChildSmartyFilter matching the query
 * @method     ChildSmartyFilter findOneOrCreate(ConnectionInterface $con = null) Return the first ChildSmartyFilter matching the query, or a new ChildSmartyFilter object populated from the query conditions when no match is found
 *
 * @method     ChildSmartyFilter findOneById(int $id) Return the first ChildSmartyFilter filtered by the id column
 * @method     ChildSmartyFilter findOneByActive(int $active) Return the first ChildSmartyFilter filtered by the active column
 * @method     ChildSmartyFilter findOneByFiltertype(string $filtertype) Return the first ChildSmartyFilter filtered by the filtertype column
 * @method     ChildSmartyFilter findOneByCode(string $code) Return the first ChildSmartyFilter filtered by the code column
 *
 * @method     array findById(int $id) Return ChildSmartyFilter objects filtered by the id column
 * @method     array findByActive(int $active) Return ChildSmartyFilter objects filtered by the active column
 * @method     array findByFiltertype(string $filtertype) Return ChildSmartyFilter objects filtered by the filtertype column
 * @method     array findByCode(string $code) Return ChildSmartyFilter objects filtered by the code column
 *
 */
abstract class SmartyFilterQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \SmartyFilter\Model\Base\SmartyFilterQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'thelia', $modelName = '\\SmartyFilter\\Model\\SmartyFilter', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildSmartyFilterQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildSmartyFilterQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof \SmartyFilter\Model\SmartyFilterQuery) {
            return $criteria;
        }
        $query = new \SmartyFilter\Model\SmartyFilterQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildSmartyFilter|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = SmartyFilterTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(SmartyFilterTableMap::DATABASE_NAME);
        }
        $this->basePreSelect($con);
        if ($this->formatter || $this->modelAlias || $this->with || $this->select
         || $this->selectColumns || $this->asColumns || $this->selectModifiers
         || $this->map || $this->having || $this->joins) {
            return $this->findPkComplex($key, $con);
        } else {
            return $this->findPkSimple($key, $con);
        }
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return   ChildSmartyFilter A model object, or null if the key is not found
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT ID, ACTIVE, FILTERTYPE, CODE FROM smarty_filter WHERE ID = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            $obj = new ChildSmartyFilter();
            $obj->hydrate($row);
            SmartyFilterTableMap::addInstanceToPool($obj, (string) $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return ChildSmartyFilter|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($dataFetcher);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ObjectCollection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection($this->getDbName());
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($dataFetcher);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return ChildSmartyFilterQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        return $this->addUsingAlias(SmartyFilterTableMap::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ChildSmartyFilterQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        return $this->addUsingAlias(SmartyFilterTableMap::ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildSmartyFilterQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(SmartyFilterTableMap::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(SmartyFilterTableMap::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SmartyFilterTableMap::ID, $id, $comparison);
    }

    /**
     * Filter the query on the active column
     *
     * Example usage:
     * <code>
     * $query->filterByActive(1234); // WHERE active = 1234
     * $query->filterByActive(array(12, 34)); // WHERE active IN (12, 34)
     * $query->filterByActive(array('min' => 12)); // WHERE active > 12
     * </code>
     *
     * @param     mixed $active The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildSmartyFilterQuery The current query, for fluid interface
     */
    public function filterByActive($active = null, $comparison = null)
    {
        if (is_array($active)) {
            $useMinMax = false;
            if (isset($active['min'])) {
                $this->addUsingAlias(SmartyFilterTableMap::ACTIVE, $active['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($active['max'])) {
                $this->addUsingAlias(SmartyFilterTableMap::ACTIVE, $active['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SmartyFilterTableMap::ACTIVE, $active, $comparison);
    }

    /**
     * Filter the query on the filtertype column
     *
     * Example usage:
     * <code>
     * $query->filterByFiltertype('fooValue');   // WHERE filtertype = 'fooValue'
     * $query->filterByFiltertype('%fooValue%'); // WHERE filtertype LIKE '%fooValue%'
     * </code>
     *
     * @param     string $filtertype The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildSmartyFilterQuery The current query, for fluid interface
     */
    public function filterByFiltertype($filtertype = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($filtertype)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $filtertype)) {
                $filtertype = str_replace('*', '%', $filtertype);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(SmartyFilterTableMap::FILTERTYPE, $filtertype, $comparison);
    }

    /**
     * Filter the query on the code column
     *
     * Example usage:
     * <code>
     * $query->filterByCode('fooValue');   // WHERE code = 'fooValue'
     * $query->filterByCode('%fooValue%'); // WHERE code LIKE '%fooValue%'
     * </code>
     *
     * @param     string $code The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildSmartyFilterQuery The current query, for fluid interface
     */
    public function filterByCode($code = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($code)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $code)) {
                $code = str_replace('*', '%', $code);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(SmartyFilterTableMap::CODE, $code, $comparison);
    }

    /**
     * Filter the query by a related \SmartyFilter\Model\SmartyFilterI18n object
     *
     * @param \SmartyFilter\Model\SmartyFilterI18n|ObjectCollection $smartyFilterI18n  the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildSmartyFilterQuery The current query, for fluid interface
     */
    public function filterBySmartyFilterI18n($smartyFilterI18n, $comparison = null)
    {
        if ($smartyFilterI18n instanceof \SmartyFilter\Model\SmartyFilterI18n) {
            return $this
                ->addUsingAlias(SmartyFilterTableMap::ID, $smartyFilterI18n->getId(), $comparison);
        } elseif ($smartyFilterI18n instanceof ObjectCollection) {
            return $this
                ->useSmartyFilterI18nQuery()
                ->filterByPrimaryKeys($smartyFilterI18n->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterBySmartyFilterI18n() only accepts arguments of type \SmartyFilter\Model\SmartyFilterI18n or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the SmartyFilterI18n relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildSmartyFilterQuery The current query, for fluid interface
     */
    public function joinSmartyFilterI18n($relationAlias = null, $joinType = 'LEFT JOIN')
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('SmartyFilterI18n');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'SmartyFilterI18n');
        }

        return $this;
    }

    /**
     * Use the SmartyFilterI18n relation SmartyFilterI18n object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \SmartyFilter\Model\SmartyFilterI18nQuery A secondary query class using the current class as primary query
     */
    public function useSmartyFilterI18nQuery($relationAlias = null, $joinType = 'LEFT JOIN')
    {
        return $this
            ->joinSmartyFilterI18n($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'SmartyFilterI18n', '\SmartyFilter\Model\SmartyFilterI18nQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildSmartyFilter $smartyFilter Object to remove from the list of results
     *
     * @return ChildSmartyFilterQuery The current query, for fluid interface
     */
    public function prune($smartyFilter = null)
    {
        if ($smartyFilter) {
            $this->addUsingAlias(SmartyFilterTableMap::ID, $smartyFilter->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the smarty_filter table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(SmartyFilterTableMap::DATABASE_NAME);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            SmartyFilterTableMap::clearInstancePool();
            SmartyFilterTableMap::clearRelatedInstancePool();

            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $affectedRows;
    }

    /**
     * Performs a DELETE on the database, given a ChildSmartyFilter or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or ChildSmartyFilter object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
     public function delete(ConnectionInterface $con = null)
     {
         if (null === $con) {
             $con = Propel::getServiceContainer()->getWriteConnection(SmartyFilterTableMap::DATABASE_NAME);
         }

         $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(SmartyFilterTableMap::DATABASE_NAME);

         $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();


            SmartyFilterTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            SmartyFilterTableMap::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
     }

    // i18n behavior

    /**
     * Adds a JOIN clause to the query using the i18n relation
     *
     * @param     string $locale Locale to use for the join condition, e.g. 'fr_FR'
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'. Defaults to left join.
     *
     * @return    ChildSmartyFilterQuery The current query, for fluid interface
     */
    public function joinI18n($locale = 'en_US', $relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $relationName = $relationAlias ? $relationAlias : 'SmartyFilterI18n';

        return $this
            ->joinSmartyFilterI18n($relationAlias, $joinType)
            ->addJoinCondition($relationName, $relationName . '.Locale = ?', $locale);
    }

    /**
     * Adds a JOIN clause to the query and hydrates the related I18n object.
     * Shortcut for $c->joinI18n($locale)->with()
     *
     * @param     string $locale Locale to use for the join condition, e.g. 'fr_FR'
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'. Defaults to left join.
     *
     * @return    ChildSmartyFilterQuery The current query, for fluid interface
     */
    public function joinWithI18n($locale = 'en_US', $joinType = Criteria::LEFT_JOIN)
    {
        $this
            ->joinI18n($locale, null, $joinType)
            ->with('SmartyFilterI18n');
        $this->with['SmartyFilterI18n']->setIsWithOneToMany(false);

        return $this;
    }

    /**
     * Use the I18n relation query object
     *
     * @see       useQuery()
     *
     * @param     string $locale Locale to use for the join condition, e.g. 'fr_FR'
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'. Defaults to left join.
     *
     * @return    ChildSmartyFilterI18nQuery A secondary query class using the current class as primary query
     */
    public function useI18nQuery($locale = 'en_US', $relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinI18n($locale, $relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'SmartyFilterI18n', '\SmartyFilter\Model\SmartyFilterI18nQuery');
    }
} // SmartyFilterQuery
